document.querySelectorAll(".newsletter-form").forEach((form) => {
  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    if (!form.reportValidity()) return;
    const button = form.querySelector('button[type="submit"]');
    const status = form.querySelector(".newsletter-status");
    const original = button.textContent;
    button.disabled = true;
    button.textContent = "…";
    status.textContent = "";
    status.classList.remove("is-success");
    try {
      const response = await fetch(form.action, {
        method: "POST",
        body: new FormData(form),
        headers: { Accept: "application/json" },
      });
      const result = await response.json().catch(() => ({}));
      if (!response.ok || !result.ok)
        throw new Error(result.message || "Unable to subscribe.");
      form.reset();
      status.textContent = "THANK YOU FOR SIGNING UP. YOU’RE ON THE LIST.";
      status.classList.add("is-success");
    } catch (error) {
      status.textContent =
        error.message || "Something went wrong. Please try again.";
    } finally {
      button.disabled = false;
      button.textContent = original;
    }
  });
});
