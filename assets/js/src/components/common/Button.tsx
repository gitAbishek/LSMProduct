import React, { ReactNode, useContext } from 'react';

import Icon from './Icon';
import { ThemeContext } from '../../context/ThemeContext';
import classNames from 'classnames';

interface Props extends React.ButtonHTMLAttributes<HTMLButtonElement> {
	icon?: any;
	layout?: 'primary' | 'accent' | 'outline';
	size?: 'small' | 'medium' | 'large';
	disabled?: boolean;
	block?: boolean;
}

const Button = React.forwardRef<HTMLButtonElement, Props>((props, ref) => {
	const {
		icon,
		layout = 'outline',
		size = 'medium',
		disabled = false,
		block = false,
		className,
		...other
	} = props;
	const {
		theme: { button },
	} = useContext(ThemeContext);

	const baseStyle = button.base;
	const blockStyle = button.block;
	const sizeStyles = {
		small: button.size.small,
		medium: button.size.medium,
		large: button.size.large,
	};
	const layoutStyles = {
		primary: button.primary.base,
		accent: button.accent.base,
		outline: button.outline.base,
	};
	const activeStyles = {
		primary: button.primary.active,
		accent: button.accent.active,
		outline: button.outline.active,
	};
	const disabledStyles = {
		primary: button.primary.disabled,
		accent: button.accent.disabled,
		outline: button.outline.disabled,
	};

	const cls = classNames(
		baseStyle,
		layoutStyles[layout],
		sizeStyles[size],
		block && blockStyle,
		disabled ? disabledStyles[layout] : activeStyles[layout],
		className
	);

	return (
		<button className={cls} {...other}>
			{icon && <Icon icon={icon} className="mto-mr-1" />}
			<span>{props.children}</span>
		</button>
	);
});

export default Button;
