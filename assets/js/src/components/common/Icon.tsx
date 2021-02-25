import React from 'react';
import classNames from 'classnames';
interface Props extends React.ComponentProps<any> {
	icon?: any;
	className?: string;
}

const Icon: React.FC<Props> = (props) => {
	const { icon, className } = props;
	return <i className={classNames('mto-block mto-text-sm')}>{icon}</i>;
};

export default Icon;
