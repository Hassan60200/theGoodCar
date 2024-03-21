import {Controller} from '@hotwired/stimulus';
import {useClickOutside, useDebounce} from 'stimulus-use';


export default class extends Controller {
    static values = {
        url: String,
    }
    connect() {
        useClickOutside(this);
        useDebounce(this, {
            wait: 350
        });
    }

    static targets = ['result', 'input', 'bankSearch'];


    async onSearchInput(event) {
        $('#bank_office_search_id').val('');
        const params = new URLSearchParams({
            query: event.currentTarget.value,
            preview: 1
        });
        if (this.inputTarget.value.length >= 3) {
            const response = await fetch(`${this.urlValue}?${params.toString()}`);
            this.resultTarget.innerHTML = await response.text();

            const $banks = $('[data-entry-bank]');
            $.map($banks, item => $(item).click(() => {
                $('#bank_office_search').val(item.innerText);
                this.bankSearchTarget.value = item.dataset.entryBank;
                this.clickOutside();
            }));
        }
    }

    clickOutside() {
        this.resultTarget.innerHTML = '';
    }
}