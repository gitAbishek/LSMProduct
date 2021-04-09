import { Box, Flex, Icon, Image, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useDropzone } from 'react-dropzone';
import { BiPlus } from 'react-icons/bi';

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
		isDragActive,
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

	return (
		<Box
			transition="ease-in-out"
			border="1px"
			borderStyle="dashed"
			borderColor="gray.300"
			bg={isDragAccept ? 'green.50' : isDragReject ? 'red.50' : 'gray.50'}
			position="relative"
			h="48"
			{...getRootProps()}>
			{preview && <Image src={preview} objectFit="cover" maxH="full" />}
			<input {...getInputProps()} multiple={false} />
			{!preview && (
				<Flex
					direction="column"
					align="center"
					justify="center"
					position="absolute"
					left="0"
					right="0"
					top="0"
					bottom="0">
					<Box>
						<Icon
							as={BiPlus}
							h="12"
							w="12"
							color={isDragActive ? 'blue.500' : 'gray.500'}
						/>
					</Box>
					<Text>{__('Upload an Image here', 'masteriyo')}</Text>
				</Flex>
			)}
		</Box>
	);
};

export default ImageUpload;
