import {Controller} from '@hotwired/stimulus';


export default class extends Controller {
    static values = {
        url: String,

    }

    connect() {
        console.log('work');
    }

    static targets = ['result', 'input'];


    async onSearchInput(event) {
        const selectValue = document.getElementById('car_brand').value;
        console.log(selectValue);
        const params = new URLSearchParams({
            query: selectValue,
            search: event.target.value,
            preview: 1
        });


        try {
            const response = await fetch(`${this.urlValue}?${params}`);
            console.log(response);
        } catch (e) {
            console.log(e);
        }
    }

}