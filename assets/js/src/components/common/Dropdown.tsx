import React, { ReactNode, useState } from 'react';

import { Popover } from 'react-tiny-popover';
import classNames from 'classnames';

interface Props extends React.ComponentPropsWithRef<'div'> {
	content: ReactNode | any;
	positions?: ('left' | 'right' | 'top' | 'bottom')[];
	align?: 'start' | 'end' | 'center';
	autoClose?: boolean;
}

const Dropdown = React.forwardRef<HTMLDivElement, Props>((props, ref) => {
	const {
		positions = ['bottom'],
		align = 'start',
		className,
		children,
		content,
		autoClose,
		...other
	} = props;
	const [open, setOpen] = useState(false);

	return (
		<div className={classNames('mto-dropdown', className)} {...other}>
			<Popover
				isOpen={open}
				positions={positions}
				align={align}
				onClickOutside={() => setOpen(false)}
				content={
					<div onClick={() => autoClose && setOpen(false)}>{content}</div>
				}>
				<div className="mto-dropdown-trigger" onClick={() => setOpen(!open)}>
					{children}
				</div>
			</Popover>
		</div>
	);
});

export default Dropdown;
