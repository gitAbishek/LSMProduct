import React from 'react';
import classNames from 'classnames';

interface Props extends React.HTMLAttributes<HTMLDivElement> {}

const ModalFooter = React.forwardRef<HTMLElement, Props>(function ModalFooter(
	props,
	ref
) {
	const { children, className, ...other } = props;

	return (
		<footer
			className={classNames(
				'mto-flex mto-flex-col mto-items-center mto-justify-end mto-px-6  mto-py-2 mto--mx-6 mto--mb-4 mto-space-x-4 sm:mto-flex-row mto-bg-gray-50',
				className
			)}
			ref={ref}
			{...other}>
			{children}
		</footer>
	);
});

export default ModalFooter;
