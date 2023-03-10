import { Button, ButtonProps } from '@chakra-ui/react';
import React from 'react';

/**
 * Ref: https://wordpress.stackexchange.com/a/382291
 */

interface Props extends ButtonProps {
	modalTitle: string;
	buttonLabel?: string;
	isMultiple?: boolean;
	onSelect: any;
	isFullWidth?: boolean;
	mediaType?: string;
	size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl';
}

const MediaUploader: React.FC<Props> = (props) => {
	const {
		modalTitle,
		buttonLabel,
		isMultiple = false,
		onSelect,
		size = 'md',
		isFullWidth = false,
		mediaType = 'image',
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

	return (
		<Button
			colorScheme="primary"
			size={size}
			variant="outline"
			isFullWidth={isFullWidth}
			onClick={handleButtonClick}
			{...props}>
			{buttonLabel}
		</Button>
	);
};

export default MediaUploader;
