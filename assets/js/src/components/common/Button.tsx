import React, { useContext } from 'react';

import Icon from './Icon';
import { ThemeContext } from '../../context/ThemeContext';
import classNames from 'classnames';

interface Props extends React.ButtonHTMLAttributes<HTMLButtonElement> {
	icon?: any;
	layout?: 'default' | 'primary' | 'accent';
	size?: 'small' | 'medium' | 'large';
}

const Button: React.FC<Props> = (props) => {
	const { icon, layout, size, className, ...other } = props;
	const {
		theme: { button },
	} = useContext(ThemeContext);

	const baseStyle = button.base;
	const cls = classNames(baseStyle, className);

	return (
		<button className={cls} {...other}>
			{icon && <Icon icon={icon} className="mto-mr-1" />}
			<span>{props.children}</span>
		</button>
	);
};

export default Button;
