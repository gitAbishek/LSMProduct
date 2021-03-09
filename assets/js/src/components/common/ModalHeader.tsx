import React from 'react';
import classNames from 'classnames';

interface Props extends React.HTMLAttributes<HTMLDivElement> {}

const ModalHeader = React.forwardRef<HTMLParagraphElement, Props>(
	function ModalHeader(props, ref) {
		const { children, className, ...other } = props;

		return (
			<p
				className={classNames(
					'mto-mb-4 mto-text-lg mto-font-semibold mto-text-gray-700',
					className
				)}
				ref={ref}
				{...other}>
				{children}
			</p>
		);
	}
);

export default ModalHeader;
