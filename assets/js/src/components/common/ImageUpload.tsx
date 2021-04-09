import { Box, Center, Image } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Icon from 'Components/common/Icon';
import React, { useEffect, useState } from 'react';
import { useDropzone } from 'react-dropzone';

import { Plus } from '../../assets/icons';

interface Props {
	setFile: any;
}

const ImageUpload: React.FC<Props> = (props) => {
	const { setFile } = props;
	const [preview, setPreview] = useState<any>(null);

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
		if (acceptedFiles.length) {
			setFile(acceptedFiles[0]);
			setPreview(URL.createObjectURL(acceptedFiles[0]));
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
			{preview && <Image src={preview} objectFit="cover" maxH="full" />}
			<input {...getInputProps()} multiple={false} />
			{!preview && (
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
