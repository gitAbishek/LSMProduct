import React from 'react';
import { ThemeContext } from '../../context/ThemeContext';
import classNames from 'classnames';
import { useContext } from 'react';

interface Props extends React.ComponentPropsWithRef<'textarea'> {
	disabled?: boolean;
}

const Textarea = React.forwardRef<HTMLTextAreaElement, Props>((props, ref) => {
	const { className, disabled, ...other } = props;

	const {
		theme: { input },
	} = useContext(ThemeContext);

	const baseStyle = input.base;
	const cls = classNames(baseStyle, className);

	return <textarea className={cls} ref={ref} {...other} />;
});

export default Textarea;
