import Icon from 'Components/common/Icon';
import { Plus } from '../../assets/icons';
import React from 'react';
import classNames from 'classnames';

interface Props {}

const AddNewButton: React.FC<Props> = (props) => {
	return (
		<button
			{...props}
			className={classNames(
				'mto-mt-8 mto-flex mto-items-center mto-cursor-pointer mto-transition-all mto-duration-300 mto-ease-in-out hover:mto-text-primary'
			)}>
			<Icon
				icon={<Plus />}
				className="mto-w-8 mto-h-8 mto-bg-primary mto-rounded-full mto-flex mto-justify-center mto-items-center mto-text-white mto-text-lg mto-mr-2"
			/>
			{props.children}
		</button>
	);
};

export default AddNewButton;
