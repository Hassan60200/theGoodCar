import {Controller} from '@hotwired/stimulus';


export default class extends Controller {
    static values = {
        url: String,

    }

    connect() {
        console.log('work');
    }

    static targets = ['result', 'input', 'bankSearch'];


    async onSearchInput(event) {
        const selectValue = document.getElementById('car_brand').value;
        const params = new URLSearchParams({
            query: selectValue,
            preview: 1
        });
        console.log();
    }

}