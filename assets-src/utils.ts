export async function copyToClipboard(text: string) {
	try {
		await navigator.clipboard.writeText(text);
	} catch (err) {
		// console.error("Unable to copy text to clipboard:", err);
		await copyToClipboardViaExecCommand(text);
	}
}

export async function copyToClipboardViaExecCommand(text: string) {
	const textArea = document.createElement("textarea");

	textArea.value = text;
	textArea.style.position = "fixed";
	textArea.style.top = "-3px";
	textArea.style.left = "-3px";
	textArea.style.width = "1px";
	textArea.style.height = "1px";
	textArea.style.background = "transparent";
	textArea.style.opacity = "0";

	document.body.appendChild(textArea);
	textArea.focus();
	textArea.select();

	try {
		document.execCommand("copy");
	} catch (err) {
		console.error("Unable to copy text to clipboard:", err);
	}

	document.body.removeChild(textArea);
}