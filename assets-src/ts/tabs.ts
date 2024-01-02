export function setupTabs(): void {
	const tabLinkList = document.querySelectorAll(
		".heatbox-tab-nav-item a",
	) as NodeListOf<HTMLAnchorElement>;

	tabLinkList.forEach(function (tabLink) {
		tabLink.addEventListener("click", function (e) {
			const href = tabLink.href;
			const hrefValue = href ? href.split("#")[1] : "";
			switchTab(hrefValue);
		});
	});

	window.addEventListener("load", function (_e) {
		let hash = window.location.hash;
		hash = hash ? hash : "#smtp";
		const hashValue = hash.replace("#", "");

		switchTab(hashValue);
	});
}

function switchTab(tab: string): void {
	const tabLinkList = document.querySelectorAll(
		".heatbox-tab-nav-item a",
	) as NodeListOf<HTMLAnchorElement>;

	tabLinkList.forEach(function (tabLink) {
		const parentEl = tabLink.parentElement;
		if (!parentEl) return;

		const href = tabLink.href;
		const hrefValue = href ? href.split("#")[1] : "";

		if (hrefValue === tab) {
			parentEl.classList.add("active");
		} else {
			parentEl.classList.remove("active");
		}
	});

	const tabContentList = document.querySelectorAll(
		"[data-show-when-tab]",
	) as NodeListOf<HTMLElement>;

	tabContentList.forEach(function (tabContent) {
		const rawTabValue = tabContent.dataset.showWhenTab;
		const tabValues = rawTabValue ? rawTabValue.split(",") : [];
		const tabValuesTrimmed = tabValues.map((value) => value.trim());

		if (tabValuesTrimmed.includes(tab)) {
			tabContent.style.display = "block";
		} else {
			tabContent.style.display = "none";
		}
	});

	setRefererValue(tab);
}

/**
 * Set referer value for the tabs navigation of settings page.
 * This is being used to preserve the active tab after saving the settings page.
 *
 * @param {string} hashValue The hash value.
 */
function setRefererValue(hashValue: string) {
	const refererField = document.querySelector('[name="_wp_http_referer"]') as HTMLInputElement | null;

	if (refererField) {
		if (refererField.value.includes("#")) {
			const urlSplits = refererField.value.split("#");
			const url = urlSplits[0];

			refererField.value = url + "#" + hashValue;
		} else {
			refererField.value = refererField.value + "#" + hashValue;
		}
	}

	const resetLink = document.querySelector('.weed-reset-button') as HTMLAnchorElement | null;

	if (resetLink) {
		if (resetLink.href.includes("#")) {
			const urlSplits = resetLink.href.split("#");
			const url = urlSplits[0];

			resetLink.href = url + "#" + hashValue;
		} else {
			resetLink.href = resetLink.href + "#" + hashValue;
		}
	}
}
