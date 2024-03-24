import {Controller} from '@hotwired/stimulus';


export default class extends Controller {
    static values = {
        url: String,

    }

    connect() {
    }

    static targets = ['result', 'input'];


    async onSearchInput(event) {
        const selectValue = document.getElementById('car_brand').value;
        const params = new URLSearchParams({
            query: selectValue,
            search: event.target.value,
            preview: 1
        });


        try {
            const response = await fetch(`${this.urlValue}?${params}`);
            const data = await response.json();

            this.resultTarget.innerHTML = '';

            data.forEach(model => {
                const li = document.createElement('li');
                li.textContent = model.name;
                li.setAttribute('id', model.id);
                li.setAttribute('value', model.name);
                li.addEventListener('click', () => {
                    this.inputTarget.value = model.name;
                    this.resultTarget.innerHTML = '';
                });
                this.resultTarget.appendChild(li);
            });

        } catch (error) {
            console.error('Error fetching autocomplete data:', error);
        }
    }

}