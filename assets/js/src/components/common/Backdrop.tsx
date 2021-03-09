import React from 'react';
import classNames from 'classnames';

interface Props extends React.HTMLAttributes<HTMLDivElement> {}

const Backdrop = React.forwardRef<HTMLDivElement, Props>(function Backdrop(
	props,
	ref
) {
	const { className, ...other } = props;
	return (
		<div
			className={classNames(
				'mto-fixed mto-inset-0 mto-z-40 mto-items-end mto-bg-black mto-bg-opacity-50 sm:mto-items-center sm:mto-justify-center',
				className
			)}
			ref={ref}
			{...other}></div>
	);
});

export default Backdrop;
