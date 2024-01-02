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

export function startLoading(button: HTMLButtonElement | null) {
	if (!button) return;
	button.classList.add("is-loading");
}

export function stopLoading(button: HTMLButtonElement | null) {
	if (!button) return;
	button.classList.remove("is-loading");
}

export type HideNoticeProps = {
	el: HTMLElement;
	type?: 'success' | 'error' | 'failed' | 'warning' | 'info';
	msg?: string;
}

export function showNotice(props: HideNoticeProps) {
	const type = props.type ? props.type : 'success';
	const msg = props.msg ? props.msg : '';

	props.el.classList.add("is-" + type);
	props.el.innerHTML = msg;
	props.el.classList.remove("is-hidden");
}

export function hideNotice(el: HTMLElement) {
	el.classList.add("is-hidden");
	el.classList.remove("is-success");
	el.classList.remove("is-error");
	el.classList.remove("is-failed");
	el.classList.remove("is-warning");
	el.classList.remove("is-info");
	el.innerHTML = '';
}