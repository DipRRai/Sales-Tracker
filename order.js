document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.menu-item').forEach(item => {
        const countElement = item.querySelector('p');
        const incrementButtons = item.querySelectorAll('.increment');

        incrementButtons.forEach(button => {
            button.addEventListener('click', () => {
                let count = parseInt(countElement.textContent);
                
                if (button.value === '+'){
                    count++;
                } else if (count > 0){
                    count--;
                }

                countElement.textContent = count;
            })
        })
    })

    document.getElementById('submitOrder').addEventListener('click', () => {
        const orderData = [];

        document.querySelectorAll('.menu-item').forEach(item => {
            const dishName = item.querySelector('h2').textContent;
            const count = parseInt(item.querySelector('p').textContent);

            if (count > 0) {
                orderData.push({name: dishName, count: count});
            }
        })

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'process_order.php';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'order';
        input.value = JSON.stringify(orderData);
        console.log(input.value);

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    })
})