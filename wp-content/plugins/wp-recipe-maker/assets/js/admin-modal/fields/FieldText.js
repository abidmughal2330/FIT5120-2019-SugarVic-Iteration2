import React from 'react';
 
const FieldText = (props) => {
    const type = props.type ? props.type : 'text';

    return (
        <input
            type={ type }
            value={props.value}
            placeholder={props.placeholder}
            onChange={(e) => {
                props.onChange( e.target.value );
            }}
            onKeyDown={(e) => {
                if ( props.onKeyDown ) {
                    props.onKeyDown(e);
                }
            }}
        />
    );
}
export default FieldText;