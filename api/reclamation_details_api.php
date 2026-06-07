<?php
// api/reclamation_details_api.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require __DIR__ . '/../config/app.php';
require __DIR__ . '/../core/Auth.php';
// Auth::requireRole('admin', 'superviseur', 'agent', 'client');

// Validate :id from query string  →  ?id=42
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID invalide.']);
    exit;
}

// Client role can only see their own reclamation
$role     = $_SESSION['role']      ?? '';
$user_id  = $_SESSION['user_id']   ?? null;

try {

    // ----------------------------------------------------------
    // 1. CORE
    // ----------------------------------------------------------
    $stmt = $pdo->prepare("
        SELECT
            r.id, r.numero_unique, r.objet, r.description,
            r.created_at, r.updated_at, r.closed_at,

            c.id        AS client_id,
            c.nom       AS client_nom,
            c.prenom    AS client_prenom,
            c.email     AS client_email,
            c.telephone AS client_telephone,
            c.adresse   AS client_adresse,

            cat.id      AS categorie_id,
            cat.libelle AS categorie_libelle,

            p.id        AS priorite_id,
            p.libelle   AS priorite_libelle,
            p.niveau    AS priorite_niveau,

            s.id        AS statut_id,
            s.libelle   AS statut_libelle,
            s.code      AS statut_code,

            u.id        AS agent_id,
            u.nom       AS agent_nom,
            u.prenom    AS agent_prenom,
            u.email     AS agent_email,
            ra.nom      AS agent_role

        FROM reclamations r
        JOIN clients                c   ON c.id   = r.client_id
        JOIN categories_reclamation cat ON cat.id = r.categorie_id
        JOIN priorites              p   ON p.id   = r.priorite_id
        JOIN statuts                s   ON s.id   = r.statut_id
        LEFT JOIN utilisateurs      u   ON u.id   = r.agent_id
        LEFT JOIN roles             ra  ON ra.id  = u.role_id
        WHERE r.id = ?
    ");
    $stmt->execute([$id]);
    $reclamation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reclamation) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Réclamation introuvable.']);
        exit;
    }

    // Clients can only view their own reclamations
    if ($role === 'client' && (int)$reclamation['client_id'] !== (int)$user_id) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Accès refusé.']);
        exit;
    }

    // ----------------------------------------------------------
    // 2. COMMENTS  (hide internal notes from clients)
    // ----------------------------------------------------------
    $interne_filter = ($role === 'client') ? 'AND co.interne = FALSE' : '';
    $stmt = $pdo->prepare("
        SELECT
            co.id, co.contenu, co.interne, co.created_at,
            u.id      AS utilisateur_id,
            u.nom     AS utilisateur_nom,
            u.prenom  AS utilisateur_prenom,
            ro.nom    AS utilisateur_role,
            cl.id     AS client_auteur_id,
            cl.nom    AS client_auteur_nom,
            cl.prenom AS client_auteur_prenom
        FROM commentaires co
        LEFT JOIN utilisateurs u  ON u.id  = co.auteur_id
        LEFT JOIN roles        ro ON ro.id = u.role_id
        LEFT JOIN clients      cl ON cl.id = co.client_id
        WHERE co.reclamation_id = ? $interne_filter
        ORDER BY co.created_at ASC
    ");
    $stmt->execute([$id]);
    $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ----------------------------------------------------------
    // 3. ATTACHMENTS
    // ----------------------------------------------------------
    $stmt = $pdo->prepare("
        SELECT id, nom_fichier, chemin, type_mime, taille, created_at
        FROM pieces_jointes
        WHERE reclamation_id = ?
        ORDER BY created_at ASC
    ");
    $stmt->execute([$id]);
    $pieces_jointes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ----------------------------------------------------------
    // 4. ASSIGNMENT HISTORY  (staff only)
    // ----------------------------------------------------------
    $affectations = [];
    if ($role !== 'client') {
        $stmt = $pdo->prepare("
            SELECT
                a.id, a.note, a.created_at,
                ag.id      AS agent_id,
                ag.nom     AS agent_nom,
                ag.prenom  AS agent_prenom,
                sup.id     AS affecte_par_id,
                sup.nom    AS affecte_par_nom,
                sup.prenom AS affecte_par_prenom,
                ro.nom     AS affecte_par_role
            FROM affectations a
            JOIN utilisateurs ag  ON ag.id  = a.utilisateur_id
            JOIN utilisateurs sup ON sup.id = a.affecte_par
            JOIN roles        ro  ON ro.id  = sup.role_id
            WHERE a.reclamation_id = ?
            ORDER BY a.created_at DESC
        ");
        $stmt->execute([$id]);
        $affectations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ----------------------------------------------------------
    // 5. AUDIT TRAIL  (staff only)
    // ----------------------------------------------------------
    $historique = [];
    if ($role !== 'client') {
        $stmt = $pdo->prepare("
            SELECT
                h.id, h.action, h.details, h.created_at,
                u.id      AS utilisateur_id,
                u.nom     AS utilisateur_nom,
                u.prenom  AS utilisateur_prenom,
                s_old.libelle AS ancien_statut,
                s_old.code    AS ancien_statut_code,
                s_new.libelle AS nouveau_statut,
                s_new.code    AS nouveau_statut_code
            FROM historique_actions h
            LEFT JOIN utilisateurs u     ON u.id     = h.utilisateur_id
            LEFT JOIN statuts      s_old ON s_old.id = h.ancien_statut_id
            LEFT JOIN statuts      s_new ON s_new.id = h.nouveau_statut_id
            WHERE h.reclamation_id = ?
            ORDER BY h.created_at DESC
        ");
        $stmt->execute([$id]);
        $historique = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ----------------------------------------------------------
    // Response
    // ----------------------------------------------------------
    echo json_encode([
        'success'       => true,
        'reclamation'   => $reclamation,
        'commentaires'  => $commentaires,
        'pieces_jointes' => $pieces_jointes,
        'affectations'  => $affectations,
        'historique'    => $historique,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
