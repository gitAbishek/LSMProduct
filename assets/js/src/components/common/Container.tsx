import React from 'react';
import classNames from 'classnames';
interface Props {
	fluid?: boolean;
	className?: string;
}

const Container: React.FC<Props> = (props) => {
	return (
		<div className={classNames('mto-container mto-mx-auto')}>
			{props.children}
		</div>
	);
};

export default Container;
