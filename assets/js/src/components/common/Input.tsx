import React, { useContext } from 'react';

import { ThemeContext } from '../../context/ThemeContext';
import classNames from 'classnames';

interface Props extends React.ComponentPropsWithRef<'input'> {
	disabled?: boolean;
}

const Input = React.forwardRef<HTMLInputElement, Props>((props, ref) => {
	const { className, type = 'text', disabled, ...other } = props;

	const {
		theme: { input },
	} = useContext(ThemeContext);

	const baseStyle = input.base;
	const cls = classNames(baseStyle, className);

	return (
		<input
			className={cls}
			type={type}
			ref={ref}
			disabled={disabled}
			{...other}
		/>
	);
});

export default Input;
