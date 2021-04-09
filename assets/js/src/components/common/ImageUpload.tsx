import { Box, Center, Image } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Icon from 'Components/common/Icon';
import React, { useState } from 'react';
import { useDropzone } from 'react-dropzone';

import { Plus } from '../../assets/icons';

interface Props {
	onChange: any;
}

const ImageUpload: React.FC<Props> = (props) => {
	const { onChange } = props;
	const [file, setFiles] = useState<any>(null);

	const {
		getRootProps,
		getInputProps,
		isDragAccept,
		isDragReject,
	} = useDropzone({
		accept: 'image/jpeg, image/png',
		onDrop: (acceptedFiles) => onDrop(acceptedFiles),
	});

	const onDrop = (acceptedFiles: any) => {
		if (isDragAccept) {
			setFiles(
				Object.assign(acceptedFiles[0], {
					image: URL.createObjectURL(acceptedFiles[0]),
				})
			);
		}
	};

	const boxStyles = {
		transition: 'ease-in-out',
		border: '1px',
		borderStyle: 'dashed',
		borderColor: 'gray.300',

		bg: isDragAccept ? 'green.100' : isDragReject ? 'red.100' : 'gray.50',
		h: 'xs',
	};

	return (
		<Box sx={boxStyles} {...getRootProps()}>
			{file && <Image src={file.image} objectFit="cover" maxH="full" />}
			<input {...getInputProps({ onChange })} multiple={false} />
			{!file && (
				<Center>
					<span>
						<Icon icon={<Plus />}></Icon>
					</span>
					<span>{__('Upload an Image here', 'masteriyo')}</span>
				</Center>
			)}
		</Box>
	);
};

export default ImageUpload;
