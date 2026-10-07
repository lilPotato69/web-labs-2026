document.getElementById("excursionForm").addEventListener("submit", function(e) {
    // Мы НЕ отменяем отправку (нет preventDefault)
    // Просто показываем пользователю, что он ввел
    const username = document.querySelector("[name='username']").value;
    const date = document.querySelector("[name='date']").value;
    alert("Вы записываетесь на экскурсию!\nИмя: " + username + "\nДата: " + date);
    // После закрытия alert форма отправится на process.php
});