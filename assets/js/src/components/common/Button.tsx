import Icon from './Icon';
import React from 'react';
import classNames from 'classnames';
interface Props extends React.ButtonHTMLAttributes<HTMLButtonElement> {
	icon?: any;
	appearance?: 'default' | 'primary' | 'accent';
	size?: 'small' | 'medium' | 'large';
}

const Button: React.ForwardRefRenderFunction<HTMLButtonElement, Props> = (
	props,
	ref
) => {
	const { icon, appearance, size, className } = props;

	return (
		<button
			className={classNames(
				`mto-flex mto-justify-center mto-items-center mto-border mto-border-solid mto-font-medium mto-rounded-sm mto-transition-all mto-duration-300 mto-ease-in-out ${
					className || ''
				}`,
				{
					'mto-px-2 mto-py-2 mto-text-xs': size === 'small',
					'mto-px-4 mto-py-3 mto-text-xs': size === 'medium',
					'mto-px-5 mto-py-4 mto-text-s': size === 'large',
					'mto-border-gray-300 hover:mto-border-primary hover:mto-text-primary':
						appearance === 'default',
					'mto-border-primary mto-bg-primary mto-text-white hover:mto-bg-primary-600 hover:mto-border-primary-700':
						appearance === 'primary',
					'mto-border-accent mto-bg-accent mto-text-white hover:mto-bg-accent-600 hover:mto-border-accent-700':
						appearance === 'accent',
				}
			)}>
			{icon && <Icon icon={icon} className="mto-mr-1" />}
			<span>{props.children}</span>
		</button>
	);
};

export default Button;
