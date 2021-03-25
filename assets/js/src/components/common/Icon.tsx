import React from 'react';
import classNames from 'classnames';

interface Props extends React.HTMLAttributes<HTMLElement> {
	icon?: any;
	className?: string;
} //

const Icon = React.forwardRef<HTMLElement, Props>((props, ref) => {
	const { icon, className, ...other } = props;

	return (
		<i
			className={classNames('mto-icon mto-block', className)}
			ref={ref}
			{...other}>
			{icon}
		</i>
	);
});

export default Icon;
