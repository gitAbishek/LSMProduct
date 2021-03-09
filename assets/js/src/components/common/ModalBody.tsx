import React from 'react';
import classNames from 'classnames';

interface Props extends React.HTMLAttributes<HTMLDivElement> {}

const ModalBody = React.forwardRef<HTMLDivElement, Props>(function ModalBody(
	props,
	ref
) {
	const { children, className, ...other } = props;

	return (
		<div
			className={classNames(
				'mto-mb-6 mto-text-sm mto-text-gray-700',
				className
			)}
			ref={ref}
			{...other}>
			{children}
		</div>
	);
});

export default ModalBody;
