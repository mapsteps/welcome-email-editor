import { copyToClipboard } from "./utils";

export function setupTemplateTags() {
	init();

	function init() {
		const metabox = document.querySelector(".tags-heatbox");
		if (!metabox) return;

		const tags = metabox.querySelectorAll("code");
		if (!tags.length) return;

		tags.forEach((tag) => {
			tag.addEventListener("click", handleTagClick);
		});
	}

	async function handleTagClick(e: Event) {
		const tag = e.target as HTMLElement | null;
		if (!tag) return;

		const value = tag.innerText;
		if (!value) return;

		// Copy value to clipboard.
		await copyToClipboard(value);

		const notice = document.querySelector(
			".tags-heatbox .action-status"
		) as HTMLElement | null;
		if (!notice) return;

		notice.classList.add("is-shown");

		setTimeout(() => {
			notice.classList.remove("is-shown");
		}, 1500);
	}
}
