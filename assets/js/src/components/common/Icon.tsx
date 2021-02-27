import React from 'react';
import classNames from 'classnames';

interface Props extends React.HTMLAttributes<HTMLElement> {
	icon?: any;
	className?: string;
} //

const Icon = React.forwardRef<HTMLElement, Props>((props, ref) => {
	const { icon, className, ...other } = props;

	const cls = classNames('mto-icon mto-block mto-text-sm', className);

	return (
		<i className={cls} ref={ref} {...other}>
			{icon}
		</i>
	);
});

export default Icon;
