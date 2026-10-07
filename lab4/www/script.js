// --- Код из ЛР-3: Alert перед отправкой ---
const form = document.getElementById("excursionForm");
if (form) {
    form.addEventListener("submit", function() {
        const username = document.querySelector("[name='username']").value;
        const date = document.querySelector("[name='date']").value;
        alert("Вы записываетесь на экскурсию!\nИмя: " + username + "\nДата: " + date);
    });
}

// --- ШТРАФНОЕ ЗАДАНИЕ 2: Кнопка "Обновить данные" (fetch без перезагрузки) ---
const refreshBtn = document.getElementById('refreshBtn');
if (refreshBtn) {
    refreshBtn.addEventListener('click', async () => {
        refreshBtn.disabled = true;
        refreshBtn.textContent = '⏳ Загрузка...';

        try {
            const response = await fetch('api_refresh.php');
            const json = await response.json();

            // Обновляем блок с источником
            document.getElementById('api-source').textContent = json.source;

            const content = document.getElementById('api-content');

            if (json.data && json.data.error) {
                content.innerHTML = `<div class="error-box"><b>Ошибка API:</b> ${json.data.error}</div>`;
            } else if (Array.isArray(json.data)) {
                let html = '<ul>';
                json.data.slice(0, 10).forEach(place => {
                    const name = place.name || 'Без названия';
                    const rate = place.rate ? `(рейтинг: ${place.rate})` : '';
                    const kinds = place.kinds ? `— <i>${place.kinds}</i>` : '';
                    html += `<li><b>${name}</b> ${rate} ${kinds}</li>`;
                });
                html += '</ul>';
                content.innerHTML = html;
            } else {
                content.innerHTML = '<p>Пустой ответ от API.</p>';
            }
        } catch (err) {
            document.getElementById('api-content').innerHTML =
                `<div class="error-box">Ошибка соединения: ${err.message}</div>`;
        } finally {
            refreshBtn.disabled = false;
            refreshBtn.textContent = '🔄 Обновить данные';
        }
    });
}