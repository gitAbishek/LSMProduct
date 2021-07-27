import {
	Button,
	ButtonGroup,
	Image,
	Modal,
	ModalBody,
	ModalCloseButton,
	ModalContent,
	ModalFooter,
	ModalHeader,
	ModalOverlay,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useQuery } from 'react-query';
import { MediaSchema } from '../../schemas';
import MediaAPI from '../../utils/media';
import ImageUpload from './ImageUpload';

interface Props {
	isOpen: boolean;
	onClose: any;
	onSucces?: any;
}
const ImageUploadModal: React.FC<Props> = (props) => {
	const { isOpen, onClose, onSucces } = props;
	const [imageUrl, setImageUrl] = useState(null);
	const imageAPi = new MediaAPI();
	const imagesQuery = useQuery('images', () => imageAPi.list());

	console.log(imagesQuery?.data);
	return (
		<Modal size="fullSpacing" isOpen={isOpen} onClose={onClose} isCentered>
			<ModalOverlay />
			<ModalContent>
				<ModalHeader
					bg="gray.800"
					borderTopRightRadius="xs"
					borderTopLeftRadius="xs"
					color="white"
					fontSize="sm">
					{__('Media Manager', 'masteriyo')}
				</ModalHeader>
				<ModalCloseButton color="white" />
				<ModalBody py="6">
					<Tabs>
						<TabList>
							<Tab>{__('Upload files')}</Tab>
							<Tab>{__('Media Library')}</Tab>
						</TabList>
						<TabPanels>
							<TabPanel>
								<ImageUpload onUploadSuccess={setImageUrl} />
							</TabPanel>
							<TabPanel>
								{imagesQuery.isSuccess &&
									imagesQuery.data.map((image: MediaSchema) => (
										<span key={image.id}>
											<Image />
										</span>
									))}
							</TabPanel>
						</TabPanels>
					</Tabs>
				</ModalBody>
				<ModalFooter bg="gray.50" borderTop="1px" borderColor="gray.100">
					<ButtonGroup>
						<Button
							colorScheme="blue"
							onClick={() => onSucces(imageUrl)}
							isDisabled={!imageUrl}>
							{__('Add Image', 'masteriyo')}
						</Button>
						<Button variant="outline" onClick={onClose}>
							{__('Cancel', 'masteriyo')}
						</Button>
					</ButtonGroup>
				</ModalFooter>
			</ModalContent>
		</Modal>
	);
};

export default ImageUploadModal;
