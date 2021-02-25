import React from 'react';
import classNames from 'classnames';
interface Props extends React.HTMLAttributes<HTMLElement> {
	icon?: any;
	className?: string;
}

const Icon: React.ForwardRefRenderFunction<HTMLElement, Props> = (
	props,
	ref
) => {
	const { icon, className, ...other } = props;
	return (
		<i
			className={classNames('mto-block mto-text-sm', className)}
			ref={ref}
			{...other}>
			{icon}
		</i>
	);
};

export default Icon;
