window.onload = function () {
    let button = document.querySelector("input[type=text]");
    button.addEventListener("input", validateY);
    button.addEventListener("focus", validateY);

    document.getElementById('clearButton').onclick = function () {
        $.ajax({
            type: 'POST',
            url: 'php/clear.php',
            success: function (serverAnswer) {
                document.getElementById('outputContainer').innerHTML = serverAnswer;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Произошла ошибка:', textStatus, errorThrown);
            }
        });
    }


    document.getElementById('sendButton').onclick = function () {
        if (validateY()) {
            let x = document.querySelector('input[name="x-input"]:checked').value;
            let y = document.getElementById('y-value').value;
            let r = document.getElementById('r-value').value;

            y = y.replace(',', '.');

            console.log({ x, y, r });
            $.ajax({
                type: 'POST',
                url: 'php/mainbc.php',
                data: { "x-value": x, "y-value": y, "r-value": r },
                success: function (serverAnswer) {
                    console.log(serverAnswer)
                    let ans = toTable(serverAnswer);
                    document.getElementById('outputContainer').innerHTML += ans;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Произошла ошибка:', textStatus, errorThrown);
                }
            });
        }
    }

    $.ajax({
        type: 'GET',
        url: 'php/mainbc.php',
        success: function (serverAnswer) {

            for (var i = 0; i < serverAnswer.length; i++) {
                console.log(serverAnswer)
                let ans = toTable(serverAnswer[i]);
                document.getElementById('outputContainer').innerHTML += ans;

            }
            console.log(serverAnswer);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Произошла ошибка:', textStatus, errorThrown);
        }
    });


}


function toTable(data) {
    let time = new Date(data.time*1000).toLocaleTimeString();
    return `<tr>
    <td>${data.x}</td>
    <td>${data.y}</td>
    <td>${data.r}</td>
    <td>${data.hit}</td>
    <td>${time}</td>
    <td>${data.execution_time}</td>
    </tr>`
}