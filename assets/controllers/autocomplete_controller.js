import {Controller} from '@hotwired/stimulus';


export default class extends Controller {
    static values = {
        url: String,

    }

    connect() {
    }

    static targets = ['result', 'input', 'inputDep', 'resultDep'];


    async onSearchInputModel(event) {
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


    async onSearchInputDepartement(event) {
        const selectValue = document.getElementById('car_region').value;
        const params = new URLSearchParams({
            query: selectValue,
            search: event.target.value,
            preview: 1
        });

        try {
            const response = await fetch(`${this.urlValue}?${params}`);
            const data = await response.json();
            data.forEach(departement => {
                const li = document.createElement('li');
                li.textContent = departement.name;
                li.setAttribute('id', departement.id);
                li.setAttribute('value', departement.name);
                li.addEventListener('click', () => {
                    this.inputDepTarget.value = departement.name;
                    this.resultDepTarget.innerHTML = '';
                });
                this.resultDepTarget.appendChild(li);
            });
        } catch (error) {
            console.error('Error fetching autocomplete data:', error);
        }
    }

}