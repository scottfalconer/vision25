(function (Drupal, once) {
  const focusableSelector = [
    "a[href]",
    "button:not([disabled])",
    "input:not([type='hidden']):not([disabled])",
    "select:not([disabled])",
    "textarea:not([disabled])",
    "[tabindex]:not([tabindex='-1'])",
  ].join(", ");

  const getFocusableElements = (container) => Array.from(container.querySelectorAll(focusableSelector)).filter((element) => !element.hasAttribute("hidden") && !element.closest("[hidden]"));
  const openDialogs = new Set();

  const syncBodyDialogState = () => {
    document.body.classList.toggle("vision25-modal-open", openDialogs.size > 0);
  };

  const closeDialog = (dialog) => {
    if (!dialog || dialog.hasAttribute("hidden")) {
      return;
    }

    dialog.setAttribute("hidden", "hidden");
    openDialogs.delete(dialog);
    syncBodyDialogState();

    const trigger = dialog.__vision25Trigger;
    if (trigger instanceof HTMLElement) {
      trigger.setAttribute("aria-expanded", "false");
      trigger.focus();
    }
  };

  const openDialog = (dialog, trigger) => {
    dialog.__vision25Trigger = trigger;
    dialog.removeAttribute("hidden");
    openDialogs.add(dialog);
    syncBodyDialogState();

    if (trigger) {
      trigger.setAttribute("aria-expanded", "true");
    }

    const panel = dialog.querySelector(".vision25-modal__panel") || dialog;
    const focusable = getFocusableElements(panel);
    const target = focusable[0] || panel;
    if (target instanceof HTMLElement) {
      target.focus();
    }
  };

  Drupal.behaviors.vision25Theme = {
    attach(context) {
      once("vision25-nav", "[data-vision25-nav-toggle]", context).forEach((toggle) => {
        const targetId = toggle.getAttribute("aria-controls");
        const nav = document.getElementById(targetId);
        if (!nav) {
          return;
        }

        toggle.addEventListener("click", () => {
          const expanded = toggle.getAttribute("aria-expanded") === "true";
          toggle.setAttribute("aria-expanded", expanded ? "false" : "true");
          nav.classList.toggle("is-open", !expanded);
        });
      });

      once("vision25-reveal", "[data-scroll-reveal]", context).forEach((element) => {
        const observer = new IntersectionObserver((entries, obs) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add("is-visible");
              obs.unobserve(entry.target);
            }
          });
        }, { rootMargin: "0px 0px -80px 0px" });

        observer.observe(element);
      });

      once("vision25-hero-intro", "[data-hero-intro]", context).forEach((element) => {
        window.requestAnimationFrame(() => {
          window.requestAnimationFrame(() => {
            element.classList.add("is-visible");
          });
        });
      });

      once("vision25-triangle", "[data-triangle-animate] line", context).forEach((line) => {
        if (typeof line.getTotalLength !== "function") {
          return;
        }
        line.style.setProperty("--vision-line-length", String(line.getTotalLength()));
      });

      once("vision25-filter", "[data-filter-group]", context).forEach((group) => {
        const buttons = Array.from(group.querySelectorAll("[data-filter-value]"));
        const targetSelector = group.getAttribute("data-filter-target");
        if (!targetSelector) {
          return;
        }
        const items = Array.from(document.querySelectorAll(targetSelector));
        buttons.forEach((button) => {
          button.addEventListener("click", () => {
            const value = button.getAttribute("data-filter-value");
            buttons.forEach((candidate) => candidate.classList.remove("is-active"));
            button.classList.add("is-active");
            items.forEach((item) => {
              const matches = !value || value === "all" || item.getAttribute("data-track") === value;
              item.classList.toggle("is-hidden", !matches);
            });
          });
        });
      });

      once("vision25-modal-open", "[data-dialog-open]", context).forEach((trigger) => {
        trigger.addEventListener("click", () => {
          const selector = trigger.getAttribute("data-dialog-open");
          const dialog = selector ? document.querySelector(selector) : null;
          if (dialog) {
            openDialog(dialog, trigger);
          }
        });
      });

      once("vision25-modal-close", "[data-dialog-close]", context).forEach((trigger) => {
        trigger.addEventListener("click", () => {
          const dialog = trigger.closest("[data-dialog]");
          if (dialog) {
            closeDialog(dialog);
          }
        });
      });

      once("vision25-modal-backdrop", "[data-dialog]", context).forEach((dialog) => {
        dialog.addEventListener("click", (event) => {
          if (event.target === dialog) {
            closeDialog(dialog);
          }
        });

        dialog.addEventListener("keydown", (event) => {
          const panel = dialog.querySelector(".vision25-modal__panel") || dialog;
          if (event.key === "Escape") {
            event.preventDefault();
            closeDialog(dialog);
            return;
          }

          if (event.key !== "Tab") {
            return;
          }

          const focusable = getFocusableElements(panel);
          if (!focusable.length) {
            event.preventDefault();
            if (panel instanceof HTMLElement) {
              panel.focus();
            }
            return;
          }

          const first = focusable[0];
          const last = focusable[focusable.length - 1];
          if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
          }
          else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
          }
        });
      });

      once("vision25-toggle", "[data-toggle-group]", context).forEach((group) => {
        const buttons = Array.from(group.querySelectorAll("[data-toggle-value]"));
        const targetSelector = group.getAttribute("data-toggle-target");
        if (!targetSelector) {
          return;
        }
        const items = Array.from(document.querySelectorAll(targetSelector));
        buttons.forEach((button) => {
          button.addEventListener("click", () => {
            const value = button.getAttribute("data-toggle-value");
            buttons.forEach((candidate) => {
              candidate.classList.toggle("is-active", candidate === button);
              candidate.setAttribute("aria-selected", candidate === button ? "true" : "false");
            });
            items.forEach((item) => {
              const matches = item.getAttribute("data-toggle-item") === value;
              item.classList.toggle("is-hidden", !matches);
            });
          });
        });
      });
    },
  };
})(Drupal, once);
