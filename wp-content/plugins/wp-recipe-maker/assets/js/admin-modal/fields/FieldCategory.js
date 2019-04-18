import React from 'react';
import he from 'he';
import CreatableSelect from 'react-select/lib/Creatable';

const FieldCategory = (props) => {
    const categories = wprm_admin_modal.categories[ props.id ].terms;
    let categoryOptions = [];
    let selectedCategories = [];

    for ( let category of categories ) {
        const categoryOption = {
            value: category.term_id,
            label: he.decode( category.name ),
        };

        categoryOptions.push(categoryOption);

        if ( props.value.find((elem) => elem.term_id === category.term_id || elem.name === category.term_id ) ) {
            selectedCategories.push(categoryOption);
        }
    }

    return (
        <CreatableSelect
            isMulti
            options={categoryOptions}
            value={selectedCategories}
            placeholder="Select from list or type to create..."
            onChange={(value) => {
                let newValue = [];

                for ( let category of value ) {
                    if ( category.hasOwnProperty('__isNew__') && category.__isNew__ ) {
                        wprm_admin_modal.categories[ props.id ].terms.push({
                            term_id: category.label,
                            name: category.label,
                        });
                    }

                    let selectedCategory = wprm_admin_modal.categories[ props.id ].terms.find((cat) => cat.term_id === category.value);

                    if ( selectedCategory ) {
                        newValue.push(selectedCategory);
                    }
                }

                props.onChange(newValue);
            }}
            styles={{
                placeholder: (provided) => ({
                    ...provided,
                    color: '#444',
                    opacity: '0.333',
                }),
                control: (provided) => ({
                    ...provided,
                    backgroundColor: 'white',
                }),
                container: (provided) => ({
                    ...provided,
                    width: '100%',
                    maxWidth: props.width ? props.width : '100%',
                }),
            }}
        />
    );
}
export default FieldCategory;