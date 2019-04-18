import React from 'react';
import Select from 'react-select';
 
const FieldDropdown = (props) => {
    return (
        <Select
            options={props.options}
            value={props.options.filter(({value}) => value === props.value)}
            onChange={(option) => {
                props.onChange(option.value);
            }}
            styles={{
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
export default FieldDropdown;