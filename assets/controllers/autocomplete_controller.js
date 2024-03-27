import {Controller} from '@hotwired/stimulus';


export default class extends Controller {
    static values = {
        url: String,

    }

    connect() {
    }

    static targets = ['result', 'input', 'inputDep', 'resultDep', 'inputCity', 'resultCity'];


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
            document.getElementById('car_region').addEventListener('change', async () => {
                const response = await fetch(`${this.urlValue}?${params}`);
                const data = await response.json();
                data.forEach(departement => {
                    const option = document.createElement('option');
                    option.textContent = departement.name;
                    option.setAttribute('id', departement.id);
                    option.setAttribute('value', departement.name);
                    option.addEventListener('click', () => {
                        this.inputDepTarget.value = departement.name;
                        this.resultDepTarget.innerHTML = '';
                    });
                    this.resultDepTarget.appendChild(option);
                });
            });

        } catch (error) {
            console.error('Error fetching autocomplete data:', error);
        }
    }

    async onSearchInputCity(event) {
        const selectValue = document.getElementById('car_departement').value;
        const params = new URLSearchParams({
            query: selectValue,
            search: event.target.value,
            preview: 1
        });
        try {
            if (event.target.value.length >= 4) {
                const response = await fetch(`${this.urlValue}?${params}`);
                const data = await response.json();
                data.forEach(city => {
                    const option = document.createElement('option');
                    option.textContent = city.name;
                    option.setAttribute('id', city.id);
                    option.setAttribute('value', city.name);
                    option.addEventListener('click', () => {
                        this.inputCityTarget.value = city.name;
                        this.resultCityTarget.innerHTML = '';
                    });
                    this.resultCityTarget.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error fetching autocomplete data:', error);
        }
    }

}