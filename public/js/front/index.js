const editor = document.getElementById("editor");
const form = document.getElementById("form");
const textarea = document.getElementById("paragraph");

function execCmd(command) {
    document.execCommand(command, false, null);
    editor.focus();
}

function clearFormatting() {
    // Use execCommand removeFormat for selected text or clear all if no selection
    document.execCommand("removeFormat", false, null);

    // Optional: Also remove links
    document.execCommand("unlink", false, null);

    editor.focus();
}

// Keyboard shortcuts for easier formatting
editor.addEventListener("keydown", function (event) {
    if (event.ctrlKey) {
        switch (event.key.toLowerCase()) {
            case "b":
                event.preventDefault();
                execCmd("bold");
                break;
            case "i":
                event.preventDefault();
                execCmd("italic");
                break;
            case "u":
                event.preventDefault();
                execCmd("underline");
                break;
        }
    }
});

form.addEventListener("submit", function (event) {
    // Copy HTML content
    textarea.value = editor.innerHTML;
});
