<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    $(function () {
        $("#search-input").autocomplete({
            source: "{{ path('search_ajax') }}",
            minLength: 3,
            select: function (event, ui) {
                event.preventDefault();
                $("#search-input").val(ui.item.title);
            }
        });
    });





    const input = document.querySelector('#search-input');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    input.addEventListener('input', function () { 
        // Make AJAX request to retrieve search suggestions or results
        const query = input.value;
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const suggestions = JSON.parse(xhr.responseText);

                // Clear existing dropdown menu items
                dropdownMenu.innerHTML = '';

                // Add new dropdown menu items
                suggestions.forEach(suggestion => {
                    const li = document.createElement('li');
                    li.textContent = suggestion.title;
                    dropdownMenu.appendChild(li);
                });

                // Show or hide the dropdown menu based on whether there are suggestions or not
                if (suggestions.length > 0) {
                    dropdownMenu.parentNode.style.display = 'block';
                } else {
                    dropdownMenu.parentNode.style.display = 'none';
                }
            }
        };
        xhr.open('GET', '{{ path('search_ajax') }}?query=' + encodeURIComponent(query));
        xhr.send();
    });

    input.addEventListener('blur', function () { 
        // Hide the dropdown menu when input loses focus
        dropdownMenu.parentNode.style.display = 'none';
    });

    input.addEventListener('keydown', function (event) {
        const query = input.value;

        if (event.keyCode === 13) {
            // Check if "Enter" key is pressed
            // Make AJAX request and handle response
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const suggestions = JSON.parse(xhr.responseText);

                    // Clear existing dropdown menu items
                    dropdownMenu.innerHTML = '';

                    // Add new dropdown menu items
                    suggestions.forEach(suggestion => {
                        const li = document.createElement('li');
                        li.textContent = suggestion.title;
                        dropdownMenu.appendChild(li);
                    });

                    // Show or hide the dropdown menu based on whether there are suggestions or not
                    if (suggestions.length > 0) {
                        dropdownMenu.parentNode.style.display = 'block';
                    } else {
                        dropdownMenu.parentNode.style.display = 'none';
                    }
                }
            };
            xhr.open('GET', '{{ path('search_ajax') }}?query=' + encodeURIComponent(query));
            xhr.send();
        }
    });

    dropdownMenu.addEventListener('keydown', function (event) { 
        // Fill input with selected suggestion and hide dropdown menu
        input.value = event.target.textContent;
        dropdownMenu.parentNode.style.display = 'none';
    });
