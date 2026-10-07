// === Основная задача: обработка формы без перезагрузки ===
const form = document.getElementById("excursionForm");
const resultDiv = document.getElementById("result");

form.addEventListener("submit", function (e) {
    e.preventDefault(); // отменяем перезагрузку

    const formData = new FormData(this);
    let output = "<h3>Ваша заявка:</h3><ul>";

    for (const [name, value] of formData.entries()) {
        output += `<li><b>${name}:</b> ${value}</li>`;
    }
    output += "</ul>";

    resultDiv.innerHTML = output;
    resultDiv.classList.add("visible");

    // Сброс таймера напоминания, т.к. пользователь активен
    resetInactivityTimer();
});

// === Штрафное задание: напоминание при бездействии 15 секунд ===
let inactivityTimer;

function showInactivityWarning() {
    // Подсветим поля красным
    document.querySelectorAll("input, select").forEach(el => {
        el.classList.add("inactive");
    });

    alert("Пожалуйста, заполните форму!");

    // Уберём подсветку через 3 секунды после alert
    setTimeout(() => {
        document.querySelectorAll("input, select").forEach(el => {
            el.classList.remove("inactive");
        });
    }, 3000);
}

function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    // Убираем подсветку, если была
    document.querySelectorAll("input, select").forEach(el => {
        el.classList.remove("inactive");
    });
    inactivityTimer = setTimeout(showInactivityWarning, 15000);
}

// Запускаем таймер сразу при загрузке страницы
resetInactivityTimer();

// Сбрасываем таймер при любом действии пользователя
["mousemove", "keydown", "click", "input"].forEach(evt => {
    document.addEventListener(evt, resetInactivityTimer);
});