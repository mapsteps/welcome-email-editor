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
}
