// --- Код из ЛР-3: Alert перед отправкой ---
const form = document.getElementById("excursionForm");
if (form) {
    form.addEventListener("submit", function() {
        const username = document.querySelector("[name='username']").value;
        const date = document.querySelector("[name='date']").value;
        alert("Вы записываетесь на экскурсию!\nИмя: " + username + "\nДата: " + date);
    });
}
