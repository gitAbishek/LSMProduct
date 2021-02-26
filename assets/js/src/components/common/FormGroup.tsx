import React from 'react';
import classNames from 'classnames';

interface Props extends React.HTMLAttributes<HTMLDivElement> {}

const FormGroup = React.forwardRef<HTMLDivElement, Props>((props, ref) => {
	const { className, children, ...other } = props;

	const baseStyle = 'mto-mb-6';
	const cls = classNames(baseStyle, className);

	return (
		<div className={cls} ref={ref} {...other}>
			{children}
		</div>
	);
});

export default FormGroup;
