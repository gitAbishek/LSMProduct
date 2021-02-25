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
	const { icon, className } = props;
	return (
		<i className={classNames('mto-block mto-text-sm', className)}>{icon}</i>
	);
};

export default Icon;
