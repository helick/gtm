document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-gtm-on]').forEach(function (element) {
        const data = element.dataset;

        element.addEventListener(data.gtmOn, function () {
            const variable = data.gtmVar || 'dataLayer';
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
