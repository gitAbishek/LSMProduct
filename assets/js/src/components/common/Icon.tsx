import React from 'react';

interface Props extends React.ComponentProps<any> {
	icon?: any;
	className?: string;
}

const Icon: React.FC<Props> = (props) => {
	const { icon, className } = props;
	return <i className={className + ' mto-block'}>{icon}</i>;
};

export default Icon;
