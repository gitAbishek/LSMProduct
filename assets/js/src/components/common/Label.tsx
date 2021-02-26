import React from 'react';
import classNames from 'classnames';

interface Props extends React.ComponentPropsWithRef<'label'> {}

const Label = React.forwardRef<HTMLLabelElement, Props>((props, ref) => {
	const { className, children, ...other } = props;

	const baseStyle = 'mto-font-medium mto-mb-3';
	const cls = classNames(baseStyle, className);

	return (
		<label className={cls} ref={ref} {...other}>
			{children}
		</label>
	);
});

export default Label;
