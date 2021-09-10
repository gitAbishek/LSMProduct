import { Button, Icon } from '@chakra-ui/react';
import React from 'react';
import { IconType } from 'react-icons/lib';

/**
 * Ref: https://wordpress.stackexchange.com/a/382291
 */

interface Props {
	modalTitle: string;
	buttonLabel?: string;
	isMultiple?: boolean;
	onSelect: any;
	isFullWidth?: boolean;
	mediaType?: string;
	icon?: {
		enable?: boolean;
		name?: IconType;
	};
}

const MediaUploader: React.FC<Props> = (props) => {
	const {
		modalTitle,
		buttonLabel,
		isMultiple = false,
		onSelect,
		isFullWidth = false,
		mediaType = 'image',
		icon = {
			enable: false,
			name: null,
		},
	} = props;

	let frame: any;

	const handleButtonClick = (event: any) => {
		event.preventDefault();

		if (frame) {
			frame.open();
			return;
		}

		//@ts-ignore
		frame = wp?.media({
			title: modalTitle,
			library: {
				type: mediaType,
			},
			multiple: isMultiple,
		});

		frame.on('select', () => {
			const selectedItems = frame.state().get('selection').toJSON();

			'function' === typeof onSelect && onSelect(selectedItems);
		});

		frame.open();
	};

	return icon.enable ? (
		<Icon onClick={handleButtonClick} as={icon.name} />
	) : (
		<Button
			variant="outline"
			isFullWidth={isFullWidth}
			colorScheme="blue"
			onClick={handleButtonClick}>
			{buttonLabel}
		</Button>
	);
};

export default MediaUploader;
