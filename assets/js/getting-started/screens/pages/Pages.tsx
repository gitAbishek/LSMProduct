import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormLabel,
	Link,
	Select,
	Skeleton,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';
import API from '../../../back-end//utils/api';
import urls from '../../../back-end/constants/urls';
import { SetttingsMap } from '../../../back-end/types';
import PagesAPI from '../../../back-end/utils/pages';
interface Props {
	dashboardURL: string;
	prevStep: () => void;
	nextStep: () => void;
}

const Pages: React.FC<Props> = (props) => {
	const { dashboardURL, prevStep, nextStep } = props;
	const { register } = useFormContext();
	const settingsApi = new API(urls.settings);
	const pageAPI = new PagesAPI();
	const pagesQuery = useQuery('pages', () => pageAPI.list());
	const settingsQuery = useQuery<SetttingsMap>('settings', () =>
		settingsApi.list()
	);

	const renderPagesOption = () => {
		try {
			return (
				<>
					<option value="">{__('Select a page...', 'masteriyo')}</option>
					{pagesQuery?.data?.map(
						(page: { id: number; title: { rendered: string } }) => (
							<option value={page.id} key={page.id}>
								{page.title.rendered}
							</option>
						)
					)}
				</>
			);
		} catch (error) {
			return;
		}
	};

	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					{pagesQuery.isLoading || settingsQuery.isLoading ? (
						<>
							<Stack spacing="8">
								<Skeleton h="6" />
								<Skeleton h="6" />
								<Skeleton h="6" />
							</Stack>
						</>
					) : (
						<>
							<FormControl>
								<Flex justify="space-between" align="center">
									<FormLabel sx={{ fontWeight: 'bold' }}>
										{__('Course List', 'masteriyo')}
									</FormLabel>
									<Select
										defaultValue={
											settingsQuery?.data?.advance?.pages?.courses_page_id
										}
										w="md"
										{...register('advance.pages.courses_page_id')}>
										{renderPagesOption()}
									</Select>
								</Flex>
							</FormControl>

							<FormControl>
								<Flex justify="space-between" align="center">
									<FormLabel sx={{ fontWeight: 'bold' }}>
										{__('Learning', 'masteriyo')}
									</FormLabel>
									<Select
										defaultValue={
											settingsQuery?.data?.advance?.pages?.learn_page_id
										}
										w="md"
										{...register('advance.pages.learn_page_id')}>
										{renderPagesOption()}
									</Select>
								</Flex>
							</FormControl>

							<FormControl>
								<Flex justify="space-between" align="center">
									<FormLabel sx={{ fontWeight: 'bold' }}>
										<Text fontSize="sm">{__('My Account', 'masteriyo')}</Text>
									</FormLabel>
									<Select
										defaultValue={
											settingsQuery?.data?.advance?.pages?.myaccount_page_id
										}
										w="md"
										{...register('advance.pages.myaccount_page_id')}>
										{renderPagesOption()}
									</Select>
								</Flex>
							</FormControl>

							<FormControl>
								<Flex justify="space-between" align="center">
									<FormLabel sx={{ fontWeight: 'bold' }}>
										{__('Checkout', 'masteriyo')}
									</FormLabel>
									<Select
										defaultValue={
											settingsQuery?.data?.advance?.pages?.checkout_page_id
										}
										w="md"
										{...register('advance.pages.checkout_page_id')}>
										{renderPagesOption()}
									</Select>
								</Flex>
							</FormControl>

							<Flex justify="space-between" align="center">
								<Button
									onClick={prevStep}
									rounded="3px"
									colorScheme="blue"
									variant="outline">
									{__('Back', 'masteriyo')}
								</Button>
								<ButtonGroup>
									<Link href={dashboardURL ? dashboardURL : '#'}>
										<Button variant="ghost">
											{__('Skip to Dashboard', 'masteriyo')}
										</Button>
									</Link>
									<Button onClick={nextStep} rounded="3px" colorScheme="blue">
										{__('Next', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</Flex>
						</>
					)}
				</Stack>
			</Box>
		</Box>
	);
};

export default Pages;
