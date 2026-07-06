import os

# ==========================
# Configuration
# ==========================
ROOT_DIR = "."  # Change to your project path if needed
OUTPUT_FILE = "project_tree.txt"

IGNORE_FOLDERS = {
    "frontend",
    "backend",
    ".git",
    "node_modules",
    "dist",
    "build",
    ".next",
    ".vscode",
    ".idea",
    "__pycache__",
    ".venv",
    "venv",
}

IGNORE_FILES = {
    ".DS_Store",
    "Thumbs.db",
}


def generate_tree(directory, prefix=""):
    entries = sorted(
        [
            entry
            for entry in os.listdir(directory)
            if entry not in IGNORE_FOLDERS
            and entry not in IGNORE_FILES
        ],
        key=lambda x: (
            not os.path.isdir(os.path.join(directory, x)),
            x.lower(),
        ),
    )

    tree = []

    for index, entry in enumerate(entries):
        path = os.path.join(directory, entry)
        connector = "└── " if index == len(entries) - 1 else "├── "

        tree.append(prefix + connector + entry)

        if os.path.isdir(path):
            extension = "    " if index == len(entries) - 1 else "│   "
            tree.extend(generate_tree(path, prefix + extension))

    return tree


if __name__ == "__main__":
    root_name = os.path.basename(os.path.abspath(ROOT_DIR))

    lines = [root_name]
    lines.extend(generate_tree(ROOT_DIR))

    with open(OUTPUT_FILE, "w", encoding="utf-8") as f:
        f.write("\n".join(lines))

    print(f"Tree saved to '{OUTPUT_FILE}'")