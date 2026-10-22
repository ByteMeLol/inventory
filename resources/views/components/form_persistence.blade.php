<script>
    (() => {
        const oldInput = @json(session()->getOldInput());

        if (!oldInput || Object.keys(oldInput).length === 0) {
            return;
        }

        const valueFor = (name) => {
            const segments = name.replace(/\]/g, '').split('[');
            let value = oldInput;

            for (const segment of segments) {
                if (value === null || typeof value !== 'object' || !(segment in value)) {
                    return undefined;
                }

                value = value[segment];
            }

            return value;
        };

        document.querySelectorAll('form [name]').forEach((field) => {
            if (field.type === 'password' || field.name === '_token' || field.name === '_method') {
                return;
            }

            const value = valueFor(field.name);

            if (value === undefined || value === null) {
                return;
            }

            if (field.type === 'checkbox' || field.type === 'radio') {
                const values = Array.isArray(value) ? value.map(String) : [String(value)];
                field.checked = values.includes(field.value) || (field.value === 'on' && Boolean(value));
                return;
            }

            if (field.tagName === 'SELECT' && field.multiple) {
                const values = Array.isArray(value) ? value.map(String) : [String(value)];
                Array.from(field.options).forEach((option) => {
                    option.selected = values.includes(option.value);
                });
                return;
            }

            field.value = Array.isArray(value) ? value.join(', ') : String(value);
        });
    })();
</script>
