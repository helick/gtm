document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-gtm-on]').forEach(function (element) {
        const data = element.dataset;

        const event = data.gtmOn || 'click';
        element.addEventListener(event, function () {
            const variable = data.gtmVariable || 'dataLayer';
            window[variable] = window[variable] || [];

            const entry = {};
            data.gtmEvent && (entry.event = String(data.gtmEvent));
            data.gtmCategory && (entry.category = String(data.gtmCategory));
            data.gtmLabel && (entry.label = String(data.gtmLabel));
            data.gtmValue && (entry.value = Number(data.gtmValue));
            data.gtmFields && (entry.fields = JSON.parse(data.gtmFields));

            window[variable].push(entry);
        });
    });
});
