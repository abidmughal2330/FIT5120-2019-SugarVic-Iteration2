import React from 'react';
import Tippy from '@tippy.js/react'

const Tooltip = (props) => {
    if ( ! props.content ) {
        return props.children;
    }

    return (
        <Tippy
            content={props.content}
            zIndex={100000}
        >
            { props.children }
        </Tippy>
    );
}
export default Tooltip;